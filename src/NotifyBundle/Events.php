<?php

declare(strict_types=1);

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
    public const ISSUE_CREATED = 'issue.created';

    // @var string
    public const COMMENT_CREATED = 'comment.created';

    // @var string
    public const ISSUE_STATUS_CHANGED = 'issue.status.changed';

    // @var string
    public const REQUIREMENT_CREATED = 'requirement.created';

    // @var string
    public const CHANGELOG_CREATED = 'changelog.created';
}
