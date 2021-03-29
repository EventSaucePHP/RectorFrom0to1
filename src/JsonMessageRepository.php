<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToReadFile;

use function feof;
use function fopen;
use function fseek;
use function fwrite;
use function json_decode;
use function json_encode;
use function stream_copy_to_stream;
use function stream_get_line;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const SEEK_END;

class JsonMessageRepository implements MessageRepository
{
    /**
     * @var FilesystemOperator
     */
    private $filesystem;

    /**
     * @var MessageSerializer|null
     */
    private $messageSerializer;

    public function __construct(FilesystemOperator $filesystem, MessageSerializer $messageSerializer = null)
    {
        $this->filesystem = $filesystem;
        $this->messageSerializer = $messageSerializer ?: new ConstructingMessageSerializer();
    }

    public function persist(Message ...$messages)
    {
        if (count($messages) == 0) {
            return;
        }

        $id = $messages[0]->aggregateRootId()->toString();
        $path = "/aggregate_{$id}.log";
        $stream = fopen('php://temp', 'w+b');

        try {
            stream_copy_to_stream($this->filesystem->readStream($path), $stream);
            fseek($stream, -1, SEEK_END);
        } catch (UnableToReadFile $exception) {
        }

        foreach ($messages as $message) {
            $json = json_encode(
                $this->messageSerializer->serializeMessage($message),
                JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
            );
            fwrite($stream, $json . "\n---\n\n");
        }

        $this->filesystem->writeStream($path, $stream);
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $path = "/aggregate_{$id->toString()}.log";

        try {
            $stream = $this->filesystem->readStream($path);
        } catch (UnableToReadFile $exception) {
            return;
        }

        $payload = '';
        $version = 0;

        while ( ! feof($stream)) {
            $line = stream_get_line($stream, 1024, "\n");

            if ($line !== "---") {
                $payload .= $line . "\n";
            } else {
                if ($payload !== '') {
                    $data = json_decode($payload, true);
                    $payload = '';

                    /** @var Message $message */
                    foreach ($this->messageSerializer->unserializePayload($data) as $message) {
                        $version = $message->aggregateVersion();
                        yield $message;
                    }
                }
            }
        }

        return $version;
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        /** @var Message[]&Generator $messages */
        $messages = $this->retrieveAll($id);

        foreach ($messages as $message) {
            if ($message->header(Header::AGGREGATE_ROOT_VERSION) > $aggregateRootVersion) {
                yield $message;
            }
        }

        return $messages->getReturn();
    }
}
