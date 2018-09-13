<?php

namespace NotifyBundle;

final class Events
{
    /**
     * For the event naming conventions, see:
     * https://symfony.com/doc/current/components/event_dispatcher.html#naming-conventions.
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ISSUE_CREATED = 'issue.created';

    /*** @var string */
    const COMMENT_CREATED = 'comment.created';

    /*** @var string */
    const ISSUE_STATUS_CHANGED = 'issue.status.changed';
}
