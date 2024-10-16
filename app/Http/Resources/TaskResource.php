<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'due_date'=>$this->due_date,
            'priority_level'=>$this->priority_level,
            'status'=>$this->status,
            'user_id'=>$this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}