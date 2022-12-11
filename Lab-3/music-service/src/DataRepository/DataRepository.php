<?php

namespace MusicService\DataRepository;

interface DataRepository
{
    public function writeData(array $data): void;
    public function readData(): array;
}