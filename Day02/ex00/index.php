<?php

require_once "TemplateEngine.php";

$fileName = "test.html";
$templateName = "book_description.html";
$parameter = [
    "nom" => "Il sentiero della distruzione",
    "auteur" => "Drew Karpyshyn",
    "description" => "Dessel è un giovane che fin dall'infanzia non ha conosciuto altro che le fatiche di una vita da minatore nelle miniere di cortosite del remoto pianeta Apatros, e i continui maltrattamenti di suo padre, che si rivolge a lui con l'appellativo di Bane. Grazie alla sua istintiva affinità con la Forza, un giorno Dessel si difende da un militare della Repubblica Galattica che voleva vendicarsi su di lui per aver perso a una partita a carte, e lo uccide. Diventato un ricercato, è costretto a fuggire e si unisce alle file dell'esercito Sith nella guerra contro i Jedi e la Repubblica. Il giovane si dimostra un guerriero eccezionale e attira così l'attenzione dei suoi superiori; viene quindi invitato all'Accademia Sith su Korriban per addestrarsi nel lato oscuro della Forza, dove assume il nome Bane.\nStudiando gli antichi scritti dell'ordine, Bane inizia a mettere in discussione l'attuale dottrina Sith, destinata al collasso per via degli attriti e le ambizioni di troppi capi, e concepisce così la regola dei due, secondo la quale solo due signori dei Sith possono coesistere: un maestro che incarna il potere e un discepolo che lo brama. Quando Lord Kaan, il condottiero della Confraternita dell'Oscurità dei Sith, raduna tutto l'esercito sul pianeta Ruusan per la battaglia campale contro l'Armata della Luce, Bane elabora un piano per distruggere la setta e rivela a Kaan il segreto della bomba psichica, un'antica e potentissima arma Sith che potrebbe decidere le sorti della battaglia in loro favore. Kaan, dubitando della lealtà di Bane, invia la giovane e subdola Githany ad avvelenarlo. Bane riesce però a scampare alla morte, minacciando la figlia del guaritore Darovit per farsi curare.\nRecatosi a Rusaan, trova Kaan che, sempre più sotto pressione dall'avanzata nemica, acconsente a utilizzare la bomba psichica. I Sith si ritirano completamente nelle caverne del pianeta, quindi eseguono il rituale della bomba psichica, generando una massa di energia oscura che spazza via ogni forma di vita sul pianeta, Jedi e Sith compresi. Bane invece ha assistito da debita distanza all'esplosione della bomba mentale ed è sopravvissuto all'insaputa di tutti. Mentre si prepara a lasciare il pianeta incontra la piccola Jedi Zannah e la prende come sua apprendista.",
    "prix" => "24,00"

];

 $Engine = new TemplateEngine();
 $Engine->createFile($fileName, $templateName, $parameter);

?>