<?php

namespace App\Jobs;

use App\Models\Quiz;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateCarQuestionOptionsJob implements ShouldQueue
{
    use Queueable;

    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function handle(): void
    {
        foreach ($this->attributes as $attribute => $value) {
            if (is_null($value)) {
                continue;
            }

            $question = Quiz::where('attribute', $attribute)->first();

            if ($question) {
                $options = $question->options ?? [];

                if (!in_array($value, $options)) {
                    $options[] = $value;
                    sort($options);
                    $question->update(['options' => $options]);
                }
            }
        }
    }
}
