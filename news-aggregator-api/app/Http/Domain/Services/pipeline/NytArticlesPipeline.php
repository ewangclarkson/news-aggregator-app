<?php
namespace App\Http\Domain\Services\pipeline;

/**
 * Class NytArticlesPipeline
 *
 * This class implements the Pipeline Design Pattern to manage the
 * stages of processing articles fetched from the New York Times API.
 * It allows for the dynamic addition of processing stages and the
 * sequential execution of these stages on a given request.
 *
 * @package App\Http\Services\Implementation\pipeline
 */
class NytArticlesPipeline
{
    /**
     * @var array
     */
    protected $stages = [];

    /**
     * Add a processing stage to the pipeline.
     *
     * This method allows for the addition of a new stage to the pipeline.
     * The stage should implement a handle method to process the request.
     *
     * @param mixed $stage The stage to be added to the pipeline.
     * @return $this Returns the current instance for method chaining.
     */
    public function addStage($stage)
    {
        $this->stages[] = $stage;
        return $this;
    }

    /**
     * Process the request through the pipeline stages.
     *
     * This method iterates through each stage in the pipeline, passing
     * the request from one stage to the next. The final output from the
     * last stage is returned.
     *
     * @param mixed $request The initial request data to be processed.
     * @return mixed The final result after processing through all stages.
     */
    public function process($request)
    {
        foreach ($this->stages as $stage) {
            $request = $stage->handle($request);
        }

        return $request;
    }
}