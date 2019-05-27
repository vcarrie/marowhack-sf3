<?php

namespace Definima\MediaBundle\Event;

/**
 * @author Arthur Gribet <a.gribet@gmail.com>
 */
final class FileManagerEvents
{
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const PRE_UPDATE = 'media.pre_update';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const POST_UPDATE = 'media.post_update';

    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const PRE_DELETE_FILE = 'media.pre_delete_file';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const POST_DELETE_FILE = 'media.post_delete_file';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const PRE_DELETE_FOLDER = ' media.pre_delete_folder';
    /** @Event("Symfony\Component\EventDispatcher\GenericEvent") */
    const POST_DELETE_FOLDER = 'media.post_delete_folder';
}
