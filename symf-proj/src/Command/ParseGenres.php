<?php


namespace App\Command;


use App\Repository\GenreRepository;
use App\Service\GenresParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseGenres extends Command
{
    /**
     * @var GenresParser $genresParser
     */
    private GenresParser $genresParser;

    /**
     * @var GenreRepository $genreRepository
     */
    private GenreRepository $genreRepository;

    public function __construct(GenresParser $genresParser, GenreRepository $genreRepository)
    {
        $this->genresParser = $genresParser;
        $this->genreRepository = $genreRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parse:genres')
            ->setDescription('Парсим и записываем жанры');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $genres = $this->genreRepository->findAll();
        if (count($genres) > 0) {
            $output->writeln('Genres table is not empty!');
        } else {
            $this->genresParser->run();
            $output->writeln('Parsing and saving genres completed!');
        }
    }
}