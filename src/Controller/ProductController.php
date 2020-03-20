<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ImageUploader;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductController
 */
class ProductController extends AbstractController
{
    protected $statusCode = 200;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductRepository|ObjectRepository
     */
    private $productRepository;

    /**
     * @var ImageUploader
     */
    private $imageUploader;


    /**
     * ProductController constructor.
     * @param EntityManagerInterface $entityManager
     * @param ImageUploader $imageUploader
     */
    public function __construct(EntityManagerInterface $entityManager, ImageUploader $imageUploader)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $entityManager->getRepository('App:Product');
        $this->imageUploader = $imageUploader;
    }

    /**
     * @Route("/", name="default")
     */

    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/products/create", name="create_Product", methods="POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function createProducts(Request $request)
    {
        $product = new Product;
        dump($request);die;
        $product->setProduct($request->get('product'));
        $product->setFavoriteCount(0);
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setImageUrl($this->imageUploader->uploadImageToCloudinary($request->files->get('image')));
        $this->updateDatabase($product);

        return new JsonResponse($this->productRepository->modify($product));
    }

    /**
     * @Route("/products", name="products", methods="GET")
     * @return Response
     */
    public function products()
    {
        $products = $this->productRepository->modifyAllProduct();

        return $this->response($products);
    }

    /**
     * @Route("/products/{id}/count", methods="POST")
     * @param $id
     * @return JsonResponse
     */
    public function increaseFavoriteCount($id)
    {
        $product = $this->productRepository->find($id);
        if (! $product) {
            return new JsonResponse("Not found!", 404);
        }
        $product->setFavoriteCount($product->getFavoriteCount() + 1);
        $this->updateDatabase($product);

        return $this->response($product->getFavoriteCount());
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    function response($data) {

        return new JsonResponse($data, $this->statusCode);
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    function responseWithError($errors) {
        $errorMsg = [
            'errors' => $errors
        ];
        return new JsonResponse($errorMsg, 422);
    }
    /**
     * Accept JSON payload
     * @param Request $request
     * @return null|Request
     */
    function acceptJsonPayload(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
    /**
     * Persist and flush
     * @param $object
     */
    function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
