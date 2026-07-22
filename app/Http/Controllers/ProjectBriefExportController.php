<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Helpers\JalaliHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectBriefExportController extends Controller
{
    /**
     * آماده‌سازی ساختار داده‌های بریف جهت رندر در قالب خروجی
     */
    private function prepareBriefData(Project $project): array
    {
        $schemaBlocks = $project->brief_schema ?? [];
        $existingAnswers = $project->briefAnswer ? ($project->briefAnswer->dynamic_answers ?? []) : [];

        $categorizedAnswers = [];
        $totalFields = 0;
        $filledFields = 0;

        foreach ($schemaBlocks as $block) {
            $type = $block['type'] ?? '';
            $data = $block['data'] ?? [];
            $fieldName = $data['name'] ?? null;
            $label = $data['label'] ?? 'بدون عنوان';
            $stepTitle = !empty($data['step_title']) ? $data['step_title'] : 'گام اول: اطلاعات عمومی و اولیه پروژه';

            if (!$fieldName && $type !== 'instruction_block') continue;

            if ($type !== 'instruction_block') {
                $totalFields++;
            }

            $answerValue = null;

            if ($type === 'input_group') {
                $subfields = explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی');
                $subAnswers = [];
                $hasVal = false;
                foreach ($subfields as $idx => $sublabel) {
                    $val = $existingAnswers["{$fieldName}_" . ($idx + 1)] ?? null;
                    if (!empty($val)) $hasVal = true;
                    $subAnswers[trim($sublabel)] = $val ?? '—';
                }
                if ($hasVal) $filledFields++;
                $answerValue = $subAnswers;
            } elseif ($type === 'repeater') {
                $items = $existingAnswers[$fieldName] ?? [];
                if (!empty($items) && is_array($items)) {
                    $filledFields++;
                    $answerValue = array_filter(array_column($items, 'value'));
                } else {
                    $answerValue = [];
                }
            } elseif ($type === 'checkboxes') {
                $vals = $existingAnswers[$fieldName] ?? [];
                if (!empty($vals)) $filledFields++;
                $answerValue = is_array($vals) ? implode('، ', $vals) : $vals;
            } else {
                $val = $existingAnswers[$fieldName] ?? null;
                if (!empty($val)) $filledFields++;
                $answerValue = $val ?? '—';
            }

            $categorizedAnswers[$stepTitle][] = [
                'type' => $type,
                'label' => $label,
                'value' => $answerValue,
            ];
        }

        $completionPercent = $totalFields > 0 ? (int)round(($filledFields / $totalFields) * 100) : 0;

        return [
            'project' => $project,
            'categorizedAnswers' => $categorizedAnswers,
            'totalFields' => $totalFields,
            'filledFields' => $filledFields,
            'completionPercent' => $completionPercent,
            'createdJalali' => JalaliHelper::toJalali($project->created_at),
        ];
    }

    /**
     * خروجی PDF پرینت استاندارد متناسب با فونت وزیرمتن فارسی و RTL
     */
    public function exportPdf(Project $project)
    {
        $data = $this->prepareBriefData($project);
        $data['isPdfMode'] = true;

        return view('pdf.brief-export', $data);
    }

    /**
     * خروجی Word (.doc) استاندارد با UTF-8 BOM جهت عدم بهم‌ریختگی زبان فارسی
     */
    public function exportDoc(Project $project)
    {
        $data = $this->prepareBriefData($project);
        $data['isPdfMode'] = false;

        $htmlContent = view('pdf.brief-export', $data)->render();

        $filename = "brief-project-{$project->id}-" . date('Y-m-d') . ".doc";

        // افزودن UTF-8 BOM جهت پشتیبانی کامل مایکروسافت ورد از حروف فارسی
        $content = "\xEF\xBB\xBF" . $htmlContent;

        return response($content, 200, [
            'Content-Type' => 'application/msword; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
