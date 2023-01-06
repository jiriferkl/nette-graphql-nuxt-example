<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Elastica\ProductSearch;
use App\Model\Elastica\Resolver\ProductConnectionTypeResolver;
use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Enum\Language;
use App\ModelGenerated\Request\Query\SearchQueryRequest;
use App\ModelGenerated\Resolver\Query\SearchQueryResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class SearchQueryResolver implements SearchQueryResolverInterface
{

	public function __construct(private ProductSearch $productSearch)
	{
	}

	public function resolve(SearchQueryRequest $request, Context $context, ResolveInfo $info,): ResolverInstance
	{
		return new ResolverInstance(
			new ProductConnectionTypeResolver(),
			$this->productSearch->search($request->query, Language::from($request->lang))
		);
	}

}
