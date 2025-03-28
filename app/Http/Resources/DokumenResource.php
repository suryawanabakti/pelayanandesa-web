<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            'created_at' => $this->created_at->format('d M Y H:i:s'),
            "file" => url('storage/' . $this->file)
        ];
    }
}
