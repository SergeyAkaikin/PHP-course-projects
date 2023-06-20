<?php
declare(strict_types=1);

namespace MusicService\DataAccess\DataRepository;

use Carbon\Carbon;
use MusicService\Domain\User;
use MusicService\Http\Response;
use PDO;

class UserRepository
{

    public function __construct(private readonly PDO $pdo)
    {
    }


    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        $sql = 'select * from user;';
        $statement = $this->pdo->query($sql);
        $mapper = new \JsonMapper();
        return array_map(
            static fn ($row): User => $mapper->map($row, new User()),
            $statement->fetchAll()
        );
    }

    public function getUser(int $id): ?User
    {
        $sql = 'select * from user where id=?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);

        if ($statement->rowCount() === 0) {
            return null;
        }
        return (new \JsonMapper())->map($statement->fetch(), new User());
    }

    public function deleteUser(int $id): void
    {
        $sql = 'delete from user where id=?;';
        $statement = $this->pdo->prepare($sql);
        $flag = $statement->execute([$id]);
    }

    public function putUser(
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): void
    {
        $sql = 'insert into user(name, surname, lastname, birth_date, email, user_name)
                values (?, ?, ?, ?, ?, ?);';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$name, $surname, $lastname, $birth_date->toDateString(), $email, $user_name]);
    }
}