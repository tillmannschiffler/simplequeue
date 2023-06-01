<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

class Json
{
    private \stdClass $data;

    public static function fromString(string $possibleJson): self
    {
        return new self($possibleJson);
    }

    public static function encode(mixed $data): string
    {
        return json_encode($data);
    }

    private function __construct(string $possibleJson)
    {
        $this->ensureJsonIsValid($possibleJson);

        $this->data = json_decode($possibleJson);
    }

    private function ensureJsonIsValid(string $possibleJson): void
    {
        $decodetContent = json_decode($possibleJson);

        if (is_null($decodetContent)) {
            throw new JobInfrastructureException(json_last_error_msg());
        }

        if (! isset($decodetContent->jobId)) {
            throw new JobInfrastructureException('Missing job id in job file.');
        }

        if (! isset($decodetContent->jobPayload)) {
            throw new JobInfrastructureException('Missing payload id in job file.');
        }

        if (! is_string($decodetContent->jobId)) {
            throw new JobInfrastructureException('job id is not a string.');
        }

        if (! is_string($decodetContent->jobPayload)) {
            throw new JobInfrastructureException('jobpayload is not a string.');
        }
    }

    public function getJobId(): string
    {
        return $this->data->jobId;
    }

    public function getJobPayload(): string
    {
        return $this->data->jobPayload;
    }
}
