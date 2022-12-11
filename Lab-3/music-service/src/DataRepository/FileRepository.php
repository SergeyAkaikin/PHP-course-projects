<?php

namespace MusicService\DataRepository;

use Generator;

class FileRepository implements DataRepository
{
    public function __construct(
        private ?Generator $fileReader,
        private ?Generator $fileRecorder
    )
    {
    }

    public function writeData(array $data): void
    {
        // TODO: Implement writeData() method.
            $this->fileRecorder->send(json_encode($data));
    }

    public function readData(): array
    {
        // TODO: Implement readData() method.
        $data = '';
        foreach ($this->fileReader as $item) {
            $data .= $item;
        }
        return json_decode($data);
    }

}