<?php

namespace api\modules\v1\models;

use common\models\Comment;

class CommentList
{
    /**
     * @var integer
     */
    public int $id;
    /**
     * @var string
     */
    public string $text;
    /**
     * @var int
     */
    public int $created_at;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $surname;
    /**
     * @var array|null
     */
    public ?array $children = [];

    /**
     * @brief Постройка иерархического дерева типов расходов
     * @param object $comment
     */
    public function __construct(object $comment)
    {
        $this->id = $comment->id;
        $this->text = $comment->text;
        $this->created_at = $comment->created_at;
        $this->name = $comment->user->profile->name;
        $this->surname = $comment->user->profile->surname;

        foreach (Comment::findAll(['parent_id' => $comment->id, 'status' => Comment::APPROVED]) as $child) {
            $this->children[] = new self($child);
        }
    }
}