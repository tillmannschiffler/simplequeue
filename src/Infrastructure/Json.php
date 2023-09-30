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
        $decodedContent = json_decode($possibleJson);

        if (is_null($decodedContent)) {
            throw new JobInfrastructureException(json_last_error_msg());
        }

        if (! isset($decodedContent->jobId)) {
            throw new JobInfrastructureException('Missing job id in job file.');
        }

        if (! isset($decodedContent->jobType)) {
            throw new JobInfrastructureException('Missing job type in job file.');
        }

        if (! isset($decodedContent->jobPayload)) {
            throw new JobInfrastructureException('Missing payload in job file.');
        }

        if (! is_string($decodedContent->jobId)) {
            throw new JobInfrastructureException('job id is not a string.');
        }

        if (! is_string($decodedContent->jobType)) {
            throw new JobInfrastructureException('job type is not a string.');
        }

        if (! is_string($decodedContent->jobPayload)) {
            throw new JobInfrastructureException('job payload is not a string.');
        }
    }

    public function getJobId(): string
    {
        return $this->data->jobId;
    }

    public function getJobType(): string
    {
        return $this->data->jobType;
    }

    public function getJobPayload(): string
    {
        return $this->data->jobPayload;
    }
}
