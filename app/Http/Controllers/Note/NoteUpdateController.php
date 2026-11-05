<?php
declare(strict_types=1);

namespace App\Http\Controllers\Note;

use App\DataTransferObjects\Note\NoteUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\NoteUpdateRequest;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use App\Support\NoteHtml;
use Illuminate\Http\JsonResponse;

class NoteUpdateController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository
    ) {
    }

    public function __invoke(NoteUpdateRequest $request, string $note): JsonResponse
    {
        $dto = new NoteUpdateDto(
            $request->validated('title'),
            NoteHtml::clean($request->validated('content'))
        );

        $updated = $this->noteRepository->update($this->organizationId(), $note, $dto);

        return response()->json($updated);
    }
}
