<?php

namespace DTApi\Http\Controllers;

use DTApi\Http\Requests\BookingRequest;
use DTApi\Services\BookingService;
use Illuminate\Http\JsonResponse;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    protected BookingService $bookingService;

    /**
     * BookingController constructor.
     * @param BookingService $bookingService
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function index(BookingRequest $request): JsonResponse
    {
        $response = $request->get('user_id')
            ? $this->bookingService->getUsersJobs($request->get('user_id'))
            : $this->bookingService->getAllJobs($request);

        return response()->json($response);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $job = $this->bookingService->getJobWithTranslator($id);
        return response()->json($job);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function store(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->createBooking($request->validated(), $request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param int $id
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function update(int $id, BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->updateBooking($id, $request->validated(), $request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function immediateJobEmail(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->sendImmediateJobEmail($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function getHistory(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->getUserJobHistory($request->get('user_id'), $request);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function acceptJob(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->acceptJob($request->validated(), $request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function acceptJobWithId(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->acceptJobWithId($request->get('job_id'), $request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function cancelJob(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->cancelJob($request->validated(), $request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function endJob(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->endJob($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function customerNotCall(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->customerNotCall($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function getPotentialJobs(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->getPotentialJobs($request->__authenticatedUser);
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function distanceFeed(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->updateDistanceFeed($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function reopen(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->reopenJob($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function resendNotifications(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->resendNotifications($request->validated());
        return response()->json($response);
    }

    /**
     * @param BookingRequest $request
     * @return JsonResponse
     */
    public function resendSMSNotifications(BookingRequest $request): JsonResponse
    {
        $response = $this->bookingService->resendSMSNotifications($request->validated());
        return response()->json($response);
    }
}
