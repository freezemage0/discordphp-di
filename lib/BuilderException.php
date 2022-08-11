<?php


namespace Freezemage\Discord;

use Exception;


final class BuilderException extends Exception
{
    public static function missingDiscord(): BuilderException
    {
        return new BuilderException('DiscordPHP not found!');
    }
}