<?php
class BaseController {

    public function handleRequest($method, $data) {
        switch ($method) {
            case 'POST':
                return $this->create($data);
            case 'GET':
                $id = $this->extractIdFromUrl();
                if (!empty($id) && is_numeric($id)) {
                    return $this->read($id);
                } else {
                    return $this->getAll();
                }
            case 'PUT':
                $id = $this->extractIdFromUrl();
                if (!empty($id) && is_numeric($id)) {
                    return $this->update($id, $data);
                } else {
                    return $this->errorResponse("No ID provided in the URL for the update.");
                }
            case 'DELETE':
                $id = $this->extractIdFromUrl();
                if (!empty($id) && is_numeric($id)) {
                    return $this->delete($id);
                } else {
                    return $this->errorResponse("No ID provided in the URL for the delete.");
                }
            default:
                return $this->errorResponse("Invalid request method.");
        }
    }

    private function extractIdFromUrl() {
        $urlSegments = explode('/', $_SERVER['REQUEST_URI']);
        return end($urlSegments);
    }

    private function errorResponse($message) {
        return array(
            "success" => false,
            "message" => $message
        );
    }
}
