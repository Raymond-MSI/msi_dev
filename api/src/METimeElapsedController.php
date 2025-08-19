<?php
class METimeElapsedController
{

    private $headers;
    private $gateway;
    public function __construct(METimeElapsedGateway $gateway)
    {
        $this->headers = (array) json_decode(file_get_contents("php://input"), true);
        $this->gateway = $gateway;
    }

    public function processRequest(string $method, ?string $id): void
    {
        if($id)
        {
            $this->processResourceRequest($method, $id);
        } else
        {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $data = $this->gateway->get($id, $this->headers);

        switch ($method)
        {
            case "GET":
                if($data[1]==0)
                {
                    http_response_code(404);
                    $jsn = [
                        "title" => "GET Time Elapsed",
                        "postFields" => $this->headers,
                        "status" => "Not Found",
                        "id" => $id,
                        "message" => "id not found"
                    ];
                } else
                {
                    $jsn = [
                        "title" => "GET Time Elapsed",
                        "postFields" => $this->headers,
                        "status" => "Success",
                        "total_records" => $data[1],
                        "data" => $data[0]
                    ];
                 }
                echo json_encode($jsn);
                break;

            case "PATCH":
                $errors = $this->getValidationErrors($this->headers, false);

                if(!empty($errors))
                {
                    http_response_code(422);
                    $jsn = [
                        "title" => "PATCH Time Elapsed",
                        "postFields" => $this->headers,
                        "status" => "Cancel",
                        "message" => $errors
                    ];
                    echo json_encode($jsn);
                    exit;
                }

                if($data[1]==0)
                {
                    http_response_code(404);
                    $jsn = [
                        "title" => "PATCH Time Elapsed",
                        "id" => $id,
                        "postFields" => $this->headers,
                        "status" => "Not Found",
                        "message" => "id not found"
                    ];
                    echo json_encode($jsn);
                    exit;
                }
                $rows = $this->gateway->update($id, $data[0][0], $this->headers);

                $jsn = [
                    "title" => "PATCH Time Elapsed",
                    "id" => $id,
                    "newData" => $this->headers,
                    "oldData" => $data[0][0],
                    "status" => "Success"
                ];
                echo json_encode($jsn);
                break;

            case "DELETE":
                $rows = $this->gateway->delete($id);

                if($rows==0)
                {
                    http_response_code(404);
                    $jsn = [
                        "title" => "DELETE Time Elapsed",
                        "id" => $id,
                        "status" => "Not Found",
                        "message" => "id not found"
                    ];
                } else
                {
                    $jsn = [
                        "title" => "GET Time Elapsed",
                        "id" => $id,
                        "total_records" => $rows,
                        "status" => "Success"
                    ];
                 }
                echo json_encode($jsn);

                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequest(string $method): void
    {
        $jsn = array();
        switch($method)
        {
            case "GET":
                $data = $this->gateway->getAll($this->headers);
                if($data[1]==0)
                {
                    http_response_code(404);
                    $jsn = [
                        "title" => "GET Time Elapsed of Manage Engine",
                        "postFields" => $this->headers,
                        "status" => "Not Found",
                        "message" => "Data not found"
                    ];
                } else
                {
                    $jsn = [
                        "title" => "GET Time Elapsed of Manage Engine",
                        "postFields" => $this->headers,
                        "status" => "Success",
                        "total_records" => $data[1],
                        "data" => $data[0]
                    ];
                 }
                // echo json_encode($jsn);
                break;
            
            case "POST":
                $errors = $this->getValidationErrors($this->headers);

                if(!empty($errors))
                {
                    http_response_code(422);
                    $jsn = [
                        "title" => "POST Time Elapsed of Manage Engine",
                        "postFields" => $this->headers,
                        "status" => "Cancel",
                        "error" => $errors
                    ];
                    // echo json_encode($jsn);
                    break;
                }

                $id = $this->gateway->create($this->headers);

                http_response_code(201);
                $jsn = [
                    "title" => "POST Time Elapsed of Manage Engine",
                    "postFields" => $this->headers,
                    "status" => "Success",
                    "id" => $id
                ];
                // echo json_encode($jsn);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
        $response = json_encode($jsn);
        echo $response;
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        // if($is_new && empty($data["id"]))
        // {
        //     $errors[] = "id is required";
        // }
        // if($is_new && empty($data["display_id"]))
        // {
        //     $errors[] = "display_id is required";
        // }
        // if($is_new && empty($data["created_time"]))
        // {
        //     $errors[] = "created_time is required";
        // }
        // if($is_new && empty($data["created_by"]))
        // {
        //     $errors[] = "created_by is required";
        // }
        // if($is_new && empty($data["responded_time"]))
        // {
        //     $errors[] = "responded_time is required";
        // }
        // // if($is_new && empty($data["is_first_response_overdue"]))
        // // {
        // //     $errors[] = "is_first_response_overdue is required";
        // // }
        // // if($is_new && empty($data["resolved_time"]))
        // // {
        // //     $errors[] = "resolved_time is required";
        // // }
        // // if($is_new && empty($data["time_elapsed"]))
        // // {
        // //     $errors[] = "time_elapsed is required";
        // // }
        // // if($is_new && empty($data["is_overdue"]))
        // // {
        // //     $errors[] = "is_overdue is required";
        // // }
        // // if($is_new && empty($data["completed_time"]))
        // // {
        // //     $errors[] = "completed_time is required";
        // // }
        // if($is_new && empty($data["template"]))
        // {
        //     $errors[] = "template is required";
        // }
        // if($is_new && empty($data["request_type"]))
        // {
        //     $errors[] = "request_type is required";
        // }
        // if($is_new && empty($data["level"]))
        // {
        //     $errors[] = "level is required";
        // }
        // if($is_new && empty($data["impact"]))
        // {
        //     $errors[] = "impact is required";
        // }
        // if($is_new && empty($data["priority"]))
        // {
        //     $errors[] = "priority is required";
        // }
        // if($is_new && empty($data["urgency"]))
        // {
        //     $errors[] = "urgency is required";
        // }
        // // if($is_new && empty($data["sla"]))
        // // {
        // //     $errors[] = "sla is required";
        // // }
        // if($is_new && empty($data["status"]))
        // {
        //     $errors[] = "status is required";
        // }
        // if($is_new && empty($data["mode"]))
        // {
        //     $errors[] = "mode is required";
        // }
        // if($is_new && empty($data["category"]))
        // {
        //     $errors[] = "category is required";
        // }
        // // if($is_new && empty($data["subcategory"]))
        // // {
        // //     $errors[] = "subcategory is required";
        // // }

        return $errors;
    }
}