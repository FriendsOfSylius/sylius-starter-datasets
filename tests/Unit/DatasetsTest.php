<?php

declare(strict_types=1);

namespace SyliusStarter\Datasets\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SyliusStarter\Core\Dataset\DatasetDirectories;
use Symfony\Component\Yaml\Yaml;

final class DatasetsTest extends TestCase
{
    private const DIRECTORY = __DIR__ . '/../../datasets';

    public function testRegistersTheDatasetsDirectory(): void
    {
        static::assertSame(realpath(self::DIRECTORY), realpath(DatasetDirectories::all()['datasets'] ?? ''));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function datasets(): iterable
    {
        foreach (glob(self::DIRECTORY . '/*', \GLOB_ONLYDIR) ?: [] as $path) {
            yield basename($path) => [$path];
        }
    }

    #[DataProvider('datasets')]
    public function testDatasetIsComplete(string $path): void
    {
        $config = Yaml::parseFile($path . '/dataset.yaml');
        static::assertNotSame('', trim((string) ($config['name'] ?? '')), 'dataset.yaml has a name.');
        static::assertNotSame('', trim((string) ($config['description'] ?? '')), 'dataset.yaml has a description.');

        $collectionEntries = Yaml::parseFile($path . '/collections.yaml')['collections'] ?? [];
        $collections = array_column($collectionEntries, 'name');
        static::assertNotEmpty($collections);
        $images = [];

        // A collection image is optional, local when given.
        foreach ($collectionEntries as $collection) {
            if (!isset($collection['image'])) {
                continue;
            }

            $image = (string) $collection['image'];
            static::assertMatchesRegularExpression('#^media/[a-z0-9_]+\.webp$#', $image, $collection['name'] . ' has a local image.');
            static::assertFileExists($path . '/' . $image);
            $images[] = $image;
        }

        $products = Yaml::parseFile($path . '/products.yaml')['products'] ?? [];
        static::assertNotEmpty($products);

        foreach ($products as $product) {
            $title = (string) ($product['title'] ?? '');
            static::assertNotSame('', $title);
            static::assertContains($product['collection_name'] ?? null, $collections, $title . ' belongs to a collection.');
            static::assertGreaterThan(0, (float) ($product['price_eur'] ?? 0), $title . ' has a price.');

            // Images ship with the dataset: no download, no AI generation.
            $image = (string) ($product['image'] ?? '');
            static::assertMatchesRegularExpression('#^media/[a-z0-9_]+\.webp$#', $image, $title . ' has a local image.');
            static::assertFileExists($path . '/' . $image);
            $images[] = $image;
        }

        // A collection image is optional; when set, it ships with the dataset too.
        foreach ($collectionEntries as $collection) {
            if (null === ($collection['image'] ?? null)) {
                continue;
            }

            $image = (string) $collection['image'];
            static::assertMatchesRegularExpression('#^media/[a-z0-9_]+\.webp$#', $image, $collection['name'] . ' has a local image.');
            static::assertFileExists($path . '/' . $image);
            $images[] = $image;
        }

        $media = array_map(static fn(string $file): string => 'media/' . basename($file), glob($path . '/media/*') ?: []);
        sort($media);
        sort($images);
        static::assertSame($media, array_values(array_unique($images)), 'Every media file is used by a product or a collection.');
    }
}
