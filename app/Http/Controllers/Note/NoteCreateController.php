<?php
declare(strict_types=1);

namespace App\Http\Controllers\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\NoteCreateRequest;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use App\Support\NoteHtml;
use Illuminate\Http\JsonResponse;

class NoteCreateController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository
    ) {
    }

    public function __invoke(NoteCreateRequest $request): JsonResponse
    {
        $dto = new NoteCreateDto(
            $this->organizationId(),
            $request->validated('title'),
            NoteHtml::clean($request->validated('content')),
            new CategoryEnum($request->validated('category')),
            $request->validated('folder_id')
        );

        $note = $this->noteRepository->create($dto);

        return response()->json($note, 201);
    }
}