<?php
/**
 * 
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
 
require_once 'includes/fpdf.php';

$idVisiteur = $_SESSION['idVisiteurChoisi'];
$mois = $_SESSION['leMoisChoisi'];
$infoFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);

if ($infoFicheFrais['dejaimprimer'] == 0) 
{
$infoVisiteur = $pdo->getInfosVisiteurParId($idVisiteur);
$dateFicheFrais = getDateComplete($mois);
$infoFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$infoVoiture = $pdo->getVoitureUtilise($idVisiteur, $mois);
$infoFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$dateDuJour = traductionDateAngVersFr(date('j F Y'));
$test = 1;
$pdo->majImpressionFicheFrais($idVisiteur, $mois, $test);

class PDF extends FPDF
{
// En-tête
function Header()
{
	
    // Logo
    $this->Image('images/logo.jpg',87.5,15,35); 
    // Saut de ligne
    $this->Ln(32);
}

// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

function Titre($libelle)
{
    // Arial 12
    $this->SetFont('Times','BU',14);
    
    // Titre
    $this->Cell(0,10,$libelle,0,1,'C');
	
    // Saut de ligne
    $this->Ln(4);
}

function TitreDeux($libelle)
{
    // Arial 12
    $this->SetFont('Times','BU',12);
    
    // Titre
    $this->Cell(0,10,$libelle,0,1,'C');
	
    // Saut de ligne
    $this->Ln(4);
}

function Visiteur($libelle)
{
	 // Arial 12
    $this->SetFont('Times','',12);
	 
	// Décalage à droite
    $this->Cell(25);    
	        
    // Titre
    $this->Cell(0,10,'Visiteur : '.$libelle,0,1,'');
	
    // Saut de ligne
    $this->Ln(2);
}

function mois($libelle)
{
    // Arial 12
    $this->SetFont('Times','',12);
	
	// Décalage à droite
    $this->Cell(25);    
    
    // Titre
    $this->Cell(0,10,'Mois : '.$libelle,0,1,'');
	
    // Saut de ligne
    $this->Ln(2);
}

function voiture($libelle)
{
    // Arial 12
    $this->SetFont('Times','',12);
	
	// Décalage à droite
    $this->Cell(25);    
    
    // Titre
    $this->Cell(0,10,'Puissance vehicule : '.$libelle,0,1,'');
	
    // Saut de ligne
    $this->Ln(4);
}

function FraisForfait($header, $data, $infoVoiture)
{
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(29, 130, 225);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // En-tête
    $w = array(50, 55, 55, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = false;	
	
    foreach($data as $row)
    {
        $this->Cell($w[0],8,$row['libelle'],'LR',0,'C',$fill);
        $this->Cell($w[1],8,$row['quantite'],'LR',0,'C',$fill);
		
		
		if ($row['idfrais'] == 'KM')
		{
			$this->Cell($w[2],8,$infoVoiture['coutpuissance'],'LR',0,'C',$fill);
			$this->Cell($w[3],8,($row['quantite']*$infoVoiture['coutpuissance']),'LR',0,'C',$fill);			
		}
		else
		{
			$this->Cell($w[2],8,$row['montant'],'LR',0,'C',$fill);
			$this->Cell($w[3],8,($row['quantite']*$row['montant']),'LR',0,'C',$fill);			
		}		
        $this->Ln();
        $fill = !$fill;
    }
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
	$this->Ln(6);
}

function FraisHorsForfait($header, $data) 
{
	 // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(29, 130, 225);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // En-tête
    $w = array(50, 110, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = false;	
	 foreach($data as $row)
    {
		if ($row['refuser'] == 1) 
		{
			$this->SetFillColor(255, 164, 54);
			$this->Cell($w[0],8,$row['date'],'LR',0,'C',$fill);
			$this->Cell($w[1],8,$row['libelle'],'LR',0,'C',$fill);
			$this->Cell($w[2],8,$row['montant'],'LR',0,'C',$fill);			
			$this->Ln();
			$fill = !$fill;
			$this->SetFillColor(224,235,255);
		}
		else
		{
			$this->Cell($w[0],8,$row['date'],'LR',0,'C',$fill);
			$this->Cell($w[1],8,$row['libelle'],'LR',0,'C',$fill);
			$this->Cell($w[2],8,$row['montant'],'LR',0,'C',$fill);			
			$this->Ln();
			$fill = !$fill;
		}
		
	}
	 // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
	$this->Ln(6);
}

function Total($header) 
{
	// Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);    
    $this->SetFont('','B');   
	// Décalage à droite
    $this->Cell(105); 
	// En-tête	
    $w = array(55, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
    $this->Ln();    
    // Données
	
	
	$this->Ln(15);
}

function Signature($texte) 
{
	 // Arial 12
    $this->SetFont('Times','',12);
	
	// Décalage à droite
    $this->Cell(105);    
    
    // Titre
    $this->Cell(0,10,$texte,0,1,'');
	
    // Saut de ligne
    $this->Ln(0);
}



}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Titre('REMBOURSEMENT DE FRAIS ENGAGES');
$pdf->Visiteur($infoVisiteur['id'].'-'.$infoVisiteur['nom'].' '.$infoVisiteur['prenom']);
$pdf->mois($dateFicheFrais);
$pdf->voiture($infoVoiture['designation']);
$pdf->TitreDeux('FRAIS FORFAITAIRES');
// Titres des colonnes
$header = array('Libelle', 'Quantite', 'Montant unitaire', 'Total');
$pdf->FraisForfait($header,$infoFraisForfait,$infoVoiture);
$pdf->TitreDeux('AUTRES FRAIS');
$headerdeux = array('Date', 'Libelle', 'Montant');
$pdf->FraisHorsForfait($headerdeux,$infoFraisHorsForfait);
$headertrois = array('TOTAL', $infoFicheFrais['montantValide']);
$pdf->Total($headertrois);
$pdf->Signature('Paris, le '.$dateDuJour);
$pdf->Signature('Vu par l\'agent comptable');

$pdf->Output('D');
}
else 
{
     header('Location: index.php?uc=validerFrais&action=voirFicheFraisSuiviDePaiement&chemin=suivrePaiementFichesFrais&mois='.$mois.'&visiteur='.$idVisiteur.'&dejaimprimer=oui');
	 end();
}





		 
			
	
		
