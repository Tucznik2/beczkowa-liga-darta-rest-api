<?php
class Controller
{
    private $gateway;
    public function __construct(BarrelGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            var_dump($id);
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }
    private function processResourceRequest(string $method, string $id): void
    {
        $player = $this->gateway->get($id);

        if (!$player) {
            http_response_code(404);
            echo json_encode(['message' => "PLayer not found"]);
            return;
        }
    }
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input", true));

                $errors = $this->getValidationErrors($data);

                $errorStatus = !empty($errors);

                if ($errorStatus) {
                    http_response_code(422);
                    echo json_encode(['errors' => $errors]);
                    break;
                } else {
                    http_response_code(420);
                }

                $id = $this->gateway->create($data);

                http_response_code(201);

                echo json_encode([
                    "message" => "Product created",
                    "id" => $id
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        if ($is_new && empty($data['player_one_id']) || empty($data['player_two_id'])) {
            $errors[] = "Players name are required";
        }

        return $errors;
    }
}
