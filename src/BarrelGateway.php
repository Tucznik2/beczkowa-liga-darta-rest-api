<?php
class BarrelGateway
{
    private PDO $conn;
    public function __construct(Database $databse)
    {
        $this->conn = $databse->getConnection();
    }
    public function getAll(): array
    {
        $sql = "SELECT * FROM first_league_games";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    public function create(array $data)
    {
        $sql = "INSERT INTO first_league_games (player_one_id, player_one_legs, player_one_180, player_one_avg, player_two_id, player_two_legs, player_two_180, player_two_avg) VALUES (:player_one_id, :player_one_legs, :player_one_180, :player_one_avg, :player_two_id, :player_two_legs, :player_two_180, :player_two_avg)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":player_one_id", $data['player_one_id'], PDO::PARAM_INT);
        $stmt->bindValue(":player_one_legs", $data['player_one_legs'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":player_one_180", $data['player_one_180'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":player_one_avg", $data['player_one_avg'] ?? 0, PDO::PARAM_STR);
        $stmt->bindValue(":player_two_id", $data['player_two_id'], PDO::PARAM_INT);
        $stmt->bindValue(":player_two_legs", $data['player_two_legs'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":player_two_180", $data['player_two_180'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":player_two_avg", $data['player_two_avg'] ?? 0, PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array
    {
        $sql = "SELECT * FROM first_league WHERE player_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update($data)
    {
    }
}
