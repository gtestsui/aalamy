<?php


namespace App\Http\Controllers\DataTransferObjects\Parents;


use Illuminate\Contracts\Support\Responsable;
use Spatie\DataTransferObject\DataTransferObject;

final class ResponsePaginationData extends DataTransferObject implements Responsable
{
    public LengthAwarePaginator $paginator;

    public DataTransferObjectCollection $collection;

    public int $status = 200;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return response()->json(
            [
                'data' => $this->collection->toArray(),
                'meta' => [
                    'currentPage' => $this->paginator->currentPage(),
                    'lastPage' => $this->paginator->lastPage(),
                    'path' => $this->paginator->path(),
                    'perPage' => $this->paginator->perPage(),
                    'total' => $this->paginator->total(),
                ],
            ],
            $this->status
        );
    }

}
