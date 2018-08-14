<?php

namespace App\Response;

use Dingo\Api\Http\Response\Format\Format;
use Dingo\Api\Http\Response\Format\JsonOptionalFormatting;

class CustomResponse extends Format
{
    use JsonOptionalFormatting;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return string
     * @throws \ErrorException
     */
    public function formatEloquentModel($model)
    {
        $key = Str::singular($model->getTable());

        if (! $model::$snakeAttributes) {
            $key = Str::camel($key);
        }

        return $this->encode([$key => $model->toArray()]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @return string
     * @throws \ErrorException
     */
    public function formatEloquentCollection($collection)
    {
        if ($collection->isEmpty()) {
            return $this->encode([]);
        }

        $model = $collection->first();
        $key = Str::plural($model->getTable());

        if (! $model::$snakeAttributes) {
            $key = Str::camel($key);
        }

        return $this->encode([$key => $collection->toArray()]);
    }

    /**
     * @param array|\Illuminate\Contracts\Support\Arrayable $content
     * @return string
     * @throws \ErrorException
     */
    public function formatArray($content)
    {
        $content = $this->morphToArray($content);

        array_walk_recursive($content, function (&$value) {
            $value = $this->morphToArray($value);
        });

        $response = $content;

        if (!isset($content['status_code']) && !isset($content['message']))
        {
            $response = [
                'status_code' => 200,
                'message' => 'OK',
                'data' => $content,
            ];
        }

        return $this->encode($response);
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Morph a value to an array.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $value
     *
     * @return array
     */
    protected function morphToArray($value)
    {
        return $value instanceof Arrayable ? $value->toArray() : $value;
    }

    /**
     * @param $content
     * @return string
     * @throws \ErrorException
     */
    protected function encode($content)
    {
        $jsonEncodeOptions = [];

        // Here is a place, where any available JSON encoding options, that
        // deal with users' requirements to JSON response formatting and
        // structure, can be conveniently applied to tweak the output.

        if ($this->isJsonPrettyPrintEnabled()) {
            $jsonEncodeOptions[] = JSON_PRETTY_PRINT;
        }

        $encodedString = $this->performJsonEncoding($content, $jsonEncodeOptions);

        if ($this->isCustomIndentStyleRequired()) {
            $encodedString = $this->indentPrettyPrintedJson(
                $encodedString,
                $this->options['indent_style']
            );
        }

        return $encodedString;
    }
}