<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NewsRepository;
use App\Entity\News;

/**
 * @Route("/api/news")
 */
class NewsApiController extends AbstractController
{
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * @Route("/", name="api_news_list", methods={"GET"})
     */
    public function getNewsList(): JsonResponse
    {
        try {
            $news = $this->newsRepository->findAll();
            return $this->json(['status' => 'success', 'data' => $news], 200, [], ['groups' => ['News']]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * @Route("/{id}", name="api_news_show", methods={"GET"})
     */
    public function getNews(int $id): JsonResponse
    {
        try {
            $news = $this->newsRepository->find($id);
            if (!$news) {
                return $this->json(['message' => 'Tin tức không tồn tại.'], 404);
            }
            return $this->json(['status' => 'success', 'data' => $news], 200, [], ['groups' => ['News']]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * @Route("/", name="api_news_create", methods={"POST"})
     */
    public function createNews(Request $request, NewsRepository $newsRepository): JsonResponse
    {
        try {
            $dataJson = json_decode($request->getContent(), true);
            if (!$dataJson) {
                return $this->json(['status' => 'error', 'data' => 'Dữ liệu không hợp lệ.'], 400);
            }
            $news = new News();
            $news->setTitle($dataJson['title']);
            $news->setDescription($dataJson['description']);
            $data = $newsRepository->new($news);
            return $this->json(['status' => 'success', 'data' => $data], 201);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * @Route("/{id}", name="api_news_update", methods={"PUT"})
     */
    public function updateNews(Request $request, int $id, NewsRepository $newsRepository): JsonResponse
    {
        $news = $newsRepository->find($id);
        if (!$news) {
            return $this->json(['message' => 'Tin tức không tồn tại.'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Dữ liệu không hợp lệ.'], 400);
        }
        $news->setTitle($data['title']);
        $news->setDescription($data['description']);
        $result = $newsRepository->save($news);
        return $this->json($news, 200, [], ['groups' => ['News']]);
    }

    /**
     * @Route("/{id}", name="api_news_delete", methods={"DELETE"})
     */
    public function deleteNews(News $news, NewsRepository $newsRepository): JsonResponse
    {
        $deleted = $newsRepository->delete($news);
        if (!$deleted) {
            return $this->json(['message' => 'Tin tức không tồn tại.'], 404);
        }
        return $this->json(['message' => 'Tin tức đã bị xóa.'], 204);
    }
    
}
