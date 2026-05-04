<?php

namespace App\Notifications;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCourse extends Notification
{
    use Queueable;

    private Course $course;
    private Carbon $start;
    private Carbon $end;
    private string $subject;
    private string $message;

    public function __construct(Course $course, Carbon $start, Carbon $end, string $subject, string $message)
    {
        $this->course = $course;
        $this->start = $start;
        $this->end = $end;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->message)
            ->line('You must complete training of "' . $this->course['title'] . '" before ' . $this->end->format('j F Y'))
            ->line('Your training will started at ' . $this->start->format('j F Y'))
            ->action('Check Your Training', url('/'))
            ->line('Thank you for using our application!');
    }

}
