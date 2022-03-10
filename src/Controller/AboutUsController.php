<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{

    /**
     * @Route("/about-us", name="index_about_us")
     */
    public function showAboutUsIndex()
    {
        return $this->render('aboutus/index.html.twig');
    }

    /**
     * @Route("/about-us/neauty-science-and-proofs", name="beauty_science_and_proofs")
     */
    public function showBeautyScience()
    {
        return $this->render('aboutus/beauty_science.html.twig');
    }

    /**
     * @Route("/about-us/market-insights", name="market_insights")
     */
    public function showMarketInsights()
    {
        return $this->render('aboutus/market.html.twig');
    }

    /**
     * @Route("/about-us/our-beliefs", name="our_beliefs")
     */
    public function showOurBeliefs()
    {
        return $this->render('aboutus/beliefs.html.twig');
    }

    /**
     * @Route("/about-us/our-team-and-scientific-consortium", name="our_team")
     */
    public function showOurTeam()
    {
        return $this->render('aboutus/team.html.twig');
    }

    /**
     * @Route("/about-us/the-story-continues", name="our_story")
     */
    public function showOurStory()
    {
        return $this->render('aboutus/story.html.twig');
    }
}