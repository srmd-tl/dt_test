<?php

namespace DTApi\Services;

use DTApi\Repository\BookingRepository;
use Illuminate\Http\Request;

class BookingService
{
    private BookingRepository $repository;

    public function __construct(BookingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUsersJobs(int $userId): array
    {
        return $this->repository->getUsersJobs($userId);
    }

    public function getAllJobs(Request $request): array
    {
        return $this->repository->getAll($request);
    }

    public function getJobWithTranslator(int $id)
    {
        return $this->repository->with('translatorJobRel.user')->find($id);
    }

    public function createBooking(array $data, $user)
    {
        return $this->repository->store($user, $data);
    }

    public function updateBooking(int $id, array $data, $user)
    {
        return $this->repository->updateJob($id, $data, $user);
    }

    public function sendImmediateJobEmail(array $data)
    {
        return $this->repository->storeJobEmail($data);
    }

    public function getUserJobHistory(int $userId, Request $request)
    {
        return $this->repository->getUsersJobsHistory($userId, $request);
    }

    public function acceptJob(array $data, $user)
    {
        return $this->repository->acceptJob($data, $user);
    }

    public function acceptJobWithId(int $jobId, $user)
    {
        return $this->repository->acceptJobWithId($jobId, $user);
    }

    public function cancelJob(array $data, $user)
    {
        return $this->repository->cancelJobAjax($data, $user);
    }

    public function endJob(array $data)
    {
        return $this->repository->endJob($data);
    }

    public function customerNotCall(array $data)
    {
        return $this->repository->customerNotCall($data);
    }

    public function getPotentialJobs($user)
    {
        return $this->repository->getPotentialJobs($user);
    }

    public function updateDistanceFeed(array $data)
    {
        // Extracted logic from BookingController::distanceFeed
        $distance = $data['distance'] ?? "";
        $time = $data['time'] ?? "";
        $jobId = $data['jobid'] ?? null;
        $session = $data['session_time'] ?? "";
        $flagged = $data['flagged'] == 'true' ? 'yes' : 'no';
        $manuallyHandled = $data['manually_handled'] == 'true' ? 'yes' : 'no';
        $byAdmin = $data['by_admin'] == 'true' ? 'yes' : 'no';
        $adminComment = $data['admincomment'] ?? "";

        if ($flagged == 'yes' && $adminComment == '') {
            return "Please, add comment";
        }

        if ($time || $distance) {
            Distance::where('job_id', '=', $jobId)->update(['distance' => $distance, 'time' => $time]);
        }

        if ($adminComment || $session || $flagged || $manuallyHandled || $byAdmin) {
            Job::where('id', '=', $jobId)->update([
                'admin_comments' => $adminComment,
                'flagged' => $flagged,
                'session_time' => $session,
                'manually_handled' => $manuallyHandled,
                'by_admin' => $byAdmin
            ]);
        }

        return 'Record updated!';
    }

    public function reopenJob(array $data)
    {
        return $this->repository->reopen($data);
    }

    public function resendNotifications(array $data)
    {
        $job = $this->repository->find($data['jobid']);
        $jobData = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $jobData, '*');

        return ['success' => 'Push sent'];
    }

    public function resendSMSNotifications(array $data)
    {
        $job = $this->repository->find($data['jobid']);
        $jobData = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return ['success' => 'SMS sent'];
        } catch (\Exception $e) {
            return ['success' => $e->getMessage()];
        }
    }
}
