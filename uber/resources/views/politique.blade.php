@extends('layouts.header')

<main class="container">
    <section class="privacy-policy">
        <h1 class="text-center title">Mise en conformité avec la réglementation sur les cookies</h1>

        <div class="section">
            <h2>Présentation</h2>
            <p>Le RGPD (Règlement Général sur la Protection des Données) est un règlement établi par l’Union européenne
                qui oblige les entreprises qui collectent, traitent et stockent des données personnelles à respecter des
                normes strictes sur la manière dont elles protègent la vie privée et la sauvegarde des informations.
                Pour une entreprise comme Uber, il est primordial de respecter ces critères pour assurer la protection
                des données des utilisateurs et éviter des sanctions. Ce projet décrit les mesures prises pour garantir
                qu’Uber soit conforme au RGPD.</p>
        </div>

        <div class="section">
            <h2>Bases légales des données personnelles</h2>
            <p><strong>Consentement explicite</strong> : au moment de la collecte des données confidentielles,
                l’entreprise doit obtenir le consentement clair, libre, spécifique, éclairé et univoque de
                l’utilisateur. Il est nécessaire que l’utilisateur soit au courant de la finalité du traitement et ait
                la possibilité de refuser ou de retirer son consentement à tout moment.</p>
            <p>Dans le contexte d’Uber, il est primordial d’obtenir un consentement clair et explicite pour obtenir les
                informations de géolocalisation. L’usage de cette fonctionnalité nécessite le consentement clair de
                l’utilisateur avant d’être autorisé. Cela pourrait être une demande clairement formulée dans
                l’application (fenêtre pop-up), permettant ainsi à l'utilisateur de décider librement s'il accepte ou
                non.</p>
            <p><strong>Exécution du contrat</strong> : ceci permet à l’entreprise de traiter des données personnelles
                sans obtenir un consentement clair explicite, dès lors que cela est nécessaire à la réalisation d’un
                contrat entre l’utilisateur et l’entreprise.</p>
            <p><strong>Intérêt légitime</strong> : ce point constitue aussi une autre base légale qui permet de traiter
                les données sans consentement explicite. Cependant, cette base est soumise à une évolution d’impact pour
                s’assurer que les intérêts de l’entreprise ne contreviennent pas aux droits et aux libertés des
                utilisateurs.</p>
            <p><strong>Obligation légale</strong> : il s’agit d’une base légale qui autorise à une entreprise de traiter
                des informations personnelles lorsqu’elle est obligée de le faire pour respecter un devoir légal.</p>
        </div>

        <div class="section">
            <h2>Finalités du traitement des données personnelles</h2>
            <p>Le RGPD exige que les entreprises soient transparentes quant aux raisons pour lesquelles elles collectent
                des données personnelles et comment elles sont utilisées. La collecte doit être justifiée par des
                finalités spécifiques, légales et clairement expliquées aux utilisateurs. Voici les principales
                finalités pour lesquelles Uber collecte des données personnelles :</p>
            <ul>
                <li><strong>Exécution du contrat</strong> : l’une des principales raisons pour lesquelles Uber collecte
                    des données personnelles est l’exécution du contrat de service entre la plateforme et ses
                    utilisateurs.</li>
                <li><strong>Gestion de la relation client</strong> : Uber collecte également des informations pour
                    garantir une gestion efficace de la relation client.</li>
                <li><strong>Amélioration du service</strong> : l’analyse des données comportementales des utilisateurs
                    permet à Uber d’améliorer continuellement ses services.</li>
                <li><strong>Communicating marketing</strong> : Uber a aussi la possibilité de recueillir et d’exploiter
                    des données pour le marketing, mais cela nécessite l'accord exprimé de manière claire de la part des
                    utilisateurs.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Principe de minimisation des données</h2>
            <p>D’après le principe de minimisation, une entreprise a le devoir de collecter les données qui sont
                strictement nécessaires, dans le but d’atteindre les finalités pour lesquelles elles sont traitées.</p>
            <ul>
                <li>La suppression automatique des données de localisation après le service.</li>
                <li>Une collecte limitée.</li>
                <li>Anonymisation pour les usagers secondaires.</li>
                <li>Paramètres de contrôle pour les utilisateurs.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Droits des personnes concernées</h2>
            <p>Le RGPD impose aux utilisateurs un certain nombre de droits concernant leurs données personnelles. Tout
                d’abord, on a le droit d’accès qui va permettre à l’utilisateur d'être au courant des données
                personnelles collectées à son sujet.</p>
            <ul>
                <li>Le droit d’accès : l’utilisateur peut demander à accéder à ses données.</li>
                <li>Le droit de rectification : l’usager peut corriger les informations incorrectes ou incomplètes.</li>
                <li>Le droit à l’effacement (ou le droit à l’oubli) : l’usager peut demander la suppression de ses
                    données personnelles.</li>
                <li>Le droit à la portabilité des données : l’utilisateur peut récupérer ses données personnelles de
                    manière structurée.</li>
                <li>Le droit d’opposition : un utilisateur peut s’opposer à l’utilisation de ses données personnelles.
                </li>
            </ul>
        </div>

        <div class="section">
            <h2>Le registre de traitement</h2>
            <p>Le registre des traitements est un document important pour toute entreprise qui doit se conformer au
                RGPD, il est également défini comme un document interne de gestion qui doit être mis à jour
                régulièrement. Ce dernier contient toutes les informations sur les activités de traitement des données
                personnelles au sein de l’entreprise.</p>
        </div>

        <div class="section">
            <h2>Le rôle du DPO</h2>
            <p>Le rôle du Délégué à la Protection des Données (DPO) est fondamental pour garantir la conformité de
                l’entreprise avec les exigences du RGPD. Le DPO est responsable de plusieurs tâches essentielles, y
                compris la supervision de la conformité au RGPD et la gestion des analyses d’impact sur la protection
                des données (DPIA).</p>
        </div>

        <div class="section">
            <h2>Gestion des cookies et consentement</h2>
            <p>Pour garantir la conformité avec le RGPD, il est essentiel de respecter les règles liées à l’utilisation
                des cookies, qui nécessitent un consentement de l’utilisateur pour tout usage non-essentiel. Dans cette
                optique, Matomo a été choisi comme outil d’analyse pour son respect de la vie privée et sa conformité au
                RGPD.</p>
        </div>

        <div class="section">
            <h2>Politique de protection des données personnelles</h2>
            <p>Dans une démarche de respect du RGPD, vous pouvez trouver ci-dessous les coordonnées des personnes
                mentionnées dans les obligations du RGPD.</p>
            <ul>
                <li><strong>Responsable de l’organisme :</strong> Tanguy Abdoulvaid</li>
                <li><strong>Responsable des traitements :</strong> Eya Ayadi</li>
                <li><strong>Délégué à la Protection des Données (DPO) :</strong> Sefer Tasdemir</li>
            </ul>
        </div>

        <div class="section">
            <h2>Durée de conservation des données personnelles</h2>
            <p>Le RGPD impose de limiter la durée de conservation des données personnelles au strict nécessaire pour les
                finalités définies. Voici les durées de conservation des données par type :</p>
            <ul>
                <li>Données des utilisateurs : conservées pendant toute la durée de la relation contractuelle et 3 ans
                    après.</li>
                <li>Données fiscales comptables : conservées pendant 10 ans.</li>
                <li>Logs techniques : conservés 1 an au maximum.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Hébergement des données personnelles</h2>
            <p>Le CNIL recommande de choisir des hébergeurs conformes, capables de fournir un contrat d’hébergement
                précisant la gestion des données. Nous avons choisi OVHcloud, un fournisseur européen respectant le
                RGPD.</p>
        </div>

        <div class="section">
            <h2>Sécurité des données</h2>
            <p>Pour ce qui est de la sécurité, cela inclura le cryptage des données sensibles, la gestion des mots de
                passe et les mesures de sécurité mises en place pour prévenir les fuites ou cyberattaques.</p>
        </div>

        <div class="section">
            <h2>Outil d’analyse des données</h2>
            <p>Pour une sécurité optimale, les mots de passe et les informations bancaires des utilisateurs doivent être
                robustes. Nous avons mis en place une politique de mots de passe forts.</p>
        </div>

        <div class="section">
            <h2>Analyse juridique des clauses techniques</h2>
            <p>La conformité d'Uber au règlement général de la protection des données est une démarche proactive pour
                éviter tout contentieux. L’adoption de mesures de sécurisation des données comme le cryptage des données
                personnelles et la mise en place des mises à jour régulières des systèmes permet de minimiser les
                risques.</p>
        </div>
    </section>
</main>

@section('styles')
    <style>
        .container {
            margin: 0 auto;
            max-width: 900px;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .title {
            color: #4CAF50;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .section {
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }

        h2 {
            color: #4CAF50;
            font-size: 1.8em;
            margin-top: 0;
        }

        p {
            line-height: 1.6;
        }

        ul {
            margin-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        .section ul {
            list-style-type: disc;
        }

        strong {
            color: #333;
            font-weight: bold;
        }
    </style>
