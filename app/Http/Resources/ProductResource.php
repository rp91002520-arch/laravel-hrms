<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id'=>$this->id,
            'name'=>$this->name,
            'price'=>$this->price,
            'stock'=>$this->stock,
            'description'=>$this->description,
            'image'=>asset('storage/'.$this->image),
            'user' => $this->user ? [
            'id' => $this->user->id,
            'name' => $this->user->name,
        ] : null,


        ];
    }
}
