<?php

namespace App\Command;

use App\Entity\Fruits;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'fruits:fetch',
    description: 'Fetch Fruits ',
    hidden: false,
)]
class FetchFruitsCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client, private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        $output->writeln('Whoa!');
        $response = $this->client->request(
            'GET',
            'https://fruityvice.com/api/fruit/all'
        );
        $content = $response->getContent();
        $fruits = json_decode($content,true);
        foreach($fruits as $fruit){
            
            $this->createFruit($fruit);
        }

        #generate email
        $this->sendEmail();
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

    protected function createFruit($data)
    {
        $fruit = new Fruits();
        $found = $this->entityManager->getRepository(Fruits::class)->findOneBy(['external_id' => $data["id"]]);
        if($found) return;
        $fruit->setName($data['name']);
        $fruit->setExternalId($data['id']);
        $fruit->setFamily($data['family']);
        $fruit->setOrderr($data['order']);
        $fruit->setGenus($data['genus']);
        $fruit->setNutritions($data['nutritions']);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->entityManager->persist($fruit);

        // actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();
    }
    
    private function sendEmail()
    {
        $email = (new Email())
            ->from('jaffarhussain1011@gmail.com')
            ->to("jaffarhussain1011@gmail.com")
            ->subject('Fruits Added')
            ->text('Fruits Added')
            ->html("<div>Fruits Added</div>");
        $this->mailer->send($email);
    }
}
