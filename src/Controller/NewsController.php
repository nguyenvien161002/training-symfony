<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(NewsRepository $newsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $allNews = $newsRepository->findAll();
        $pagination = $paginator->paginate($allNews, $request->query->getInt('page', 1), 2);
        return $this->render('news/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/news/new', name: 'news_new')]
    public function createNewNews(Request $request, NewsRepository $newsRepository): Response
    {
        $form = $this->createForm(NewsType::class);
        $form->handleRequest($request);
        $title = 'Tạo mới Tin Tức';
        $handle = 'news_new';
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $title = $data->getTitle();
            $description = $data->getDescription();
            $news = $newsRepository->new($title, $description);
            if ($news) {
                $this->addFlash('success', 'Tạo mới tin tức thành công.');
            } else {
                $this->addFlash('error', 'Không thể tạo mới bài tin tức.');
            }
            return $this->redirectToRoute('app_news');
        }
        return $this->render('news/save.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'handle' => $handle, 
        ]);
    }

    #[Route('/news/edit/{id}', name: 'news_edit')]
    public function edit(Request $request, News $news, NewsRepository $newsRepository): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        $title = 'Chỉnh sửa Tin Tức';
        $handle = 'news_edit';
        if ($form->isSubmitted() && $form->isValid()) {
            $newsUpdate = $newsRepository->update($news);
            if ($newsUpdate) {
                $this->addFlash('success', 'Cập nhật thành công.');
            } else {
                $this->addFlash('error', 'Không thể cập nhật bài tin tức.');
            }
            return $this->redirectToRoute('app_news');
        }
        return $this->render('news/save.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'handle' => $handle, 
        ]);
    }

    #[Route('/news/{id}', name: 'news_detail')]
    public function viewDetailsNews(News $news, NewsRepository $newsRepository): Response
    {
        return $this->render('news/detail.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/news/delete/{id}', name: 'news_delete')]
    public function deleteNews(News $news, NewsRepository $newsRepository): Response
    {
        $deleted = $newsRepository->delete($news);
        if ($deleted) {
            $this->addFlash('success', 'Xóa tin tức thành công.');
        } else {
            $this->addFlash('error', 'Không thể xóa tin tức.');
        }
        return $this->redirectToRoute('app_news');
    }

}
