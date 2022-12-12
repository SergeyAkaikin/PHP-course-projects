<?php

namespace MusicService\DataRepository;

interface DataRepository
{
    public function writeData(array $data): void;
    public function readData(): array;
    public function readDataWithId(int $id): Object;
    public function deleteDataWithId(int $id): bool;
}