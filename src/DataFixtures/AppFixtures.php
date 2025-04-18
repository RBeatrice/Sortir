<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // === Sites ===
        $site1 = (new Site())->setNom('ENI Nantes');
        $site2 = (new Site())->setNom('ENI Rennes');
        $site3 = (new Site())->setNom('ENI Paris');

        $manager->persist($site1);
        $manager->persist($site2);
        $manager->persist($site3);

        // === Lieux ===
        $lieu1 = (new Lieu())
            ->setNom('Salle Horizon')
            ->setRue('10 rue des Lilas')
            ->setLatitude(47.218)
            ->setLongitude(-1.5536)
            ->setVille('Nantes');

        $lieu2 = (new Lieu())
            ->setNom('Parc de Rennes')
            ->setRue('25 boulevard République')
            ->setLatitude(48.117)
            ->setLongitude(-1.677)
            ->setVille('Rennes');

        $lieu3 = (new Lieu())
            ->setNom('Tour Eiffel')
            ->setRue('Champ de Mars')
            ->setLatitude(48.8584)
            ->setLongitude(2.2945)
            ->setVille('Paris');

        $manager->persist($lieu1);
        $manager->persist($lieu2);
        $manager->persist($lieu3);

        // === États ===
        $etats = [];
        foreach (['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'] as $label) {
            $etat = new Etat();
            $etat->setLibelle($label);
            $manager->persist($etat);
            $etats[] = $etat;
        }

        // === Participants ===
        $participants = [];

        // ADMIN
        $admin = new Participant();
        $admin->setNom('Admin')
            ->setPrenom('Super')
            ->setEmail('admin@eni.fr')
            ->setTelephone('0600000000')
            ->setActif(true)
            ->setAdministrateur(true)
            ->setRoles(['ROLE_ADMIN'])
            ->setSite($site1)
            ->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);
        $participants[] = $admin;

        // USER
        $user = new Participant();
        $user->setNom('Jean')
            ->setPrenom('Dupont')
            ->setEmail('jean.dupont@eni.fr')
            ->setTelephone('0612345678')
            ->setActif(true)
            ->setAdministrateur(false)
            ->setRoles(['ROLE_USER'])
            ->setSite($site1)
            ->setPassword($this->hasher->hashPassword($user, 'user123'));
        $manager->persist($user);
        $participants[] = $user;

        // 8 Autres
        for ($i = 1; $i <= 8; $i++) {
            $p = new Participant();
            $p->setNom("Nom$i")
                ->setPrenom("Prenom$i")
                ->setEmail("user$i@eni.fr")
                ->setTelephone("06000000$i")
                ->setActif(true)
                ->setAdministrateur(false)
                ->setRoles(['ROLE_USER'])
                ->setSite([$site1, $site2, $site3][$i % 3])
                ->setPassword($this->hasher->hashPassword($p, 'azerty'));
            $manager->persist($p);
            $participants[] = $p;
        }

        // === Sorties ===
        for ($i = 1; $i <= 10; $i++) {
            $sortie = new Sortie();
            $sortie->setNom("Sortie $i")
                ->setDateHeureDebut(new \DateTimeImmutable("+$i days"))
                ->setDateLimiteInscription(new \DateTimeImmutable("+".($i - 2)." days"))
                ->setDuree(90 + $i * 5)
                ->setNbInscriptionsMax(10 + $i)
                ->setInfosSortie("Description de la sortie numéro $i")
                ->setLieu([$lieu1, $lieu2, $lieu3][$i % 3])
                ->setSiteOrganisateur([$site1, $site2, $site3][$i % 3])
                ->setEtat($etats[$i % count($etats)])
                ->setOrganisateur($participants[$i % count($participants)]);

            // Ajout de 3-5 participants
            $used = [];
            while (count($used) < rand(3, 5)) {
                $index = rand(0, count($participants) - 1);
                if (!in_array($index, $used)) {
                    $sortie->addParticipant($participants[$index]);
                    $used[] = $index;
                }
            }

            $manager->persist($sortie);
        }

        $manager->flush();
    }
}
