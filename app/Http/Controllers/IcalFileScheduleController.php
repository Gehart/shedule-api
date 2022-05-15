<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Assembler\IcalFileGenerationRequestAssembler;
use App\Http\Request\IcalFileGettingRequest;
use App\Service\ConvertSchedule\IcalFileGeneratingService;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class IcalFileScheduleController extends Controller
{
    /**
     * @param IcalFileGettingRequest $icalFileGettingRequest
     * @param IcalFileGenerationRequestAssembler $icalFileRequestAssembler
     * @param IcalFileGeneratingService $icalFileGeneratingService
     * @return SymfonyResponse
     */
    public function getIcalFileForSchedule(
        IcalFileGettingRequest             $icalFileGettingRequest,
        IcalFileGenerationRequestAssembler $icalFileRequestAssembler,
        IcalFileGeneratingService          $icalFileGeneratingService,
    ): SymfonyResponse
    {
        $request = $icalFileRequestAssembler->create($icalFileGettingRequest);
        $result = $icalFileGeneratingService->convertToIcalFormat($request);

        return new SymfonyResponse($result->getContent(), SymfonyResponse::HTTP_OK, [
            'Content-type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }
}
