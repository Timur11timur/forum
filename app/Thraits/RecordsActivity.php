<?php

namespace App\Thraits;

use App\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->user()->id,
            'type' => $this->getActivityType($event),
        ]);


//        Activity::create([
//            'user_id' => auth()->user()->id,
//            'type' => $this->getActivityType($event),
//            'subject_id' => $this->id,
//            'subject_type' => get_class($this)
//        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event): string
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}
