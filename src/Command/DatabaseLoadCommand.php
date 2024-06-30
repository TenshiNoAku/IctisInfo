<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Audience;
use App\Entity\ClassTime;
use App\Entity\ClassType;
use App\Entity\Schedule;
#[AsCommand(
    name: 'database:load',
    description: 'load database with data from api',
)]
class DatabaseLoadCommand extends Command
{  
     private $client;
    private $entityManager;
    public function __construct(HttpClientInterface $client, ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->client = $client;
        $this->doctrine = $doctrine;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {	
    	gc_enable();
        $io = new SymfonyStyle($input, $output);
	$entityManager = $this->doctrine->getManager();
	
	# Adding audiences to DB
	
	$audience_name_files = [];
        $audience_info = $this->client->request('GET','https://webictis.sfedu.ru/schedule-api/?query=%D0%93-4')->toArray()["choices"];
        foreach($audience_info as $info){
        	$aud = $this->doctrine->getRepository(Audience::class)->findBy(["name" => $info['name']]);
        	if(!$aud){
        		$audience = new Audience();
			$audience->setName($info['name']);
			
			$entityManager->persist($audience);
			$io->success("created audience {$info['name']}");
	
        	}
        }
        $entityManager->flush();
        foreach($audience_info as $info){	
        	$audience_name_files[$this->doctrine->getRepository(Audience::class)->findBy(["name" => $info['name']])[0]->getId()] = $info['group'];
        }
	
	
	# Adding audiences to DB
	
	# Adding class_time to DB

        
        $weeks = array_slice($this->client->request('GET',"https://webictis.sfedu.ru/schedule-api/?group=a18.html&week=5")->toArray()["weeks"],10,3);
        $schedule = $this->client->request('GET',"https://webictis.sfedu.ru/schedule-api/?group=a18.html&week=1")->toArray()["table"]["table"];
        $class_time = array_slice($schedule[1],1);
        foreach($class_time as $time){
        	$ct = $this->doctrine->getRepository(ClassTime::class)->findBy(["classTime" => $time]);
        	if(!$ct){
        		$class_timed = new ClassTime();
			$class_timed->setClassTime($time);
			
			$entityManager->persist($class_timed);
			$io->success("created class_time {$time}");
	
        }}
        $entityManager->flush();	
        
        foreach($class_time as $time){
	$class_time_with_id[$time] = $this->doctrine->getRepository(ClassTime::class)->findBy(["classTime" => $time])[0]->getId();
	
	}
        #Adding class_time to DB
        $io->success("creating schedule... It's may take a while");
        foreach ($audience_name_files as $audience_id => $audience_file){
        unset($date);
        $date = new \DateTime('08-04-2024');
        foreach ($weeks as $week){ #ALL WEEKS
        unset($entityManager);
        $entityManager = $this->doctrine->getManager();
        unset($schedule);
        $schedule = $this->client->request('GET',"https://webictis.sfedu.ru/schedule-api/?group={$audience_file}&week={$week}")->toArray()["table"]["table"];
        $class_time = array_slice($schedule[1],1);
        $schedule_classes = array_slice($schedule,2);
        foreach ($schedule_classes as $classes){ #WEEK
        	$classes = array_slice($classes,1);
		$day_class = 0;
        	foreach ($classes as $class){ #ONE DAY
        		
        		$class_name = "";
        		$description= "";
        		$flag = 0;
        		$class = explode(" ", $class);
        		for ($i = 0; $i < count($class) ;$i++){
        			if ($flag == 1) {$description.=$class[$i] . " ";}
				else if (!str_contains($class[$i], "пр.") and !str_contains($class[$i], "экз.") and !str_contains($class[$i], "лаб.") and !str_contains($class[$i], "лек.")){
					$class_name .= $class[$i] . " ";
				}
				else{
					$description .= $class[$i] . " ";
					$flag = 1;
				}
							
			}
			
		
			
			#ADDING CLASSTYPE
			if ($class_name == " "){$class_name = "Свободно";}
			$ct = $this->doctrine->getRepository(ClassType::class)->findBy(["className" => $class_name]);
        	if(!$ct){
        		unset($class_type);
        		$class_type = new ClassType();
			$class_type->setClassName($class_name);
			
			$entityManager->persist($class_type);
			$entityManager->flush();
			#classType = NULL;
			$io->success("created class_type {$class_name}");
			
		
			
        	}	
        	
        	 $schedule = new Schedule();
		$classType = $this->doctrine->getRepository(ClassType::class)->findBy(["className" => $class_name])[0];
		$classTime = $this->doctrine->getRepository(ClassTime::class)->find($class_time_with_id[$class_time[$day_class]]);
		$audience = $this->doctrine->getRepository(Audience::class)->find($audience_id);
		$schedule->setDescription($description);
	   	$schedule->setClassType($classType);
	   	$schedule->setClassTime($classTime);
	   	$schedule->setAudience($audience);
	   	$schedule->setDate($date->format("d-m-Y"));
	   	$entityManager->persist($schedule);
		$entityManager->flush();
		unset($schedule);
		unset($classType);
		unset($classTime);
		unset($audience);
		
		$day_class+=1;
        }
	$date->modify("+ 1 day");		
			
		
        	}
        	
        	
        }}
  
        return Command::SUCCESS;
    }
}
