<?php


namespace App\Http\Controllers\DataTransferObjects\Parents;


use App\Http\Controllers\Classes\ApiResponseClass;
use Illuminate\Contracts\Support\Responsable;
use Spatie\DataTransferObject\DataTransferObject;

final class ResponseData extends DataTransferObject implements Responsable
{
    public int $status = 200;

    /** @var \Spatie\DataTransferObject\DataTransferObject|\Spatie\DataTransferObject\DataTransferObjectCollection */
    public $data;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return  ApiResponseClass::successResponse($this->data->toResponse());

    }
}
