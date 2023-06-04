<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TodoListModel;
use CodeIgniter\API\ResponseTrait;

class TodoListController extends BaseController
{
    use ResponseTrait;

    /**
     * TodoList Model.
     *
     * @var TodoListModel
     */
    protected TodoListModel $todoListModel;

    public function __construct()
    {
        $this->todoListModel = new TodoListModel();
    }

    /**
     * Render todo list view.
     *
     * @return void
     */
    public function view()
    {
        return view('todo');
    }

    /**
     * [Get] /todo
     * Get all todo list data.
     *
     * @return void
     */
    public function index()
    {
        $todoList = $this->todoListModel->findAll();

        return $this->respond([
            "msg" => "success",
            "data" => $todoList
        ]);
    }

    /**
     * [GET] /todo/{key}
     *
     * @param integer|null $key
     * @return void
     */
    public function show(?int $key = null)
    {
        if ($key === null) {
            return $this->failNotFound("Enter the the todo key");
        }

        $todo = $this->todoListModel->find($key);

        return $this->respond([
            "msg" => "success",
            "data" => $todo
        ]);
    }

    /**
     * [POST] /todo
     * Create a new todo data into database.
     *
     * @return void
     */
    public function create()
    {
        // Get the  data from request.
        $data    = $this->request->getJSON();
        $title   = $data["title"] ?? null;
        $content = $data["content"] ?? null;

        // Check if account and password is correct.
        if ($title === null || $content === null) {
            return $this->fail("Pass in data is not found.", 404);
        }

        if ($title === " " || $content === " ") {
            return $this->fail("Pass in data is not found.", 404);
        }

        // Insert data into database.
        $createdKey = $this->todoListModel->insert([
            "t_title"   => $title,
            "t_content" => $content,
            "created"   => date("Y-m-d H:i:s"),
            "updated"   => date("Y-m-d H:i:s"),
        ]);

        // Check if insert successfully.
        if ($createdKey === null) {
            return $this->fail("create failed.");
        } else {
            return $this->respond([
                "msg" => "create successfully",
            ]);
        }
    }

    
}
