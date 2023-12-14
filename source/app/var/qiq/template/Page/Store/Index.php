{{ extends ('layout/Store/page') }}
{{ $storeHelpers = storeHelpers() }}
{{ $storeContext = storeContext() }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('head_scripts') }}
{{ parentBlock () }}
<script>
  (async function () {
    const result = await fetch("{{= route('/store/me', ['storeKey' => $storeContext->getStoreKey()]) }}")
    const json = await result.json()
    console.log(json)
  })()
</script>

<script>
  (async function () {
    const result = await fetch("{{= route('/store/me/cart', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      credentials: 'include',
    })
    const json = await result.json()
    console.log(json)
  })()
</script>

<script>
  async function getCart () {
    const result = await fetch("{{= route('/store/me/cart', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
    })
    const json = await result.json()
    console.log(json)
  }

  async function addItemToCart () {
    const data = {
      cartItem: {
        productId: 1,
        productVariantId: 1,
        quantity: 1,
      },
    }
    const result = await fetch("{{= route('/store/me/cart/item', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data),
    })
    const json = await result.json()
    console.log(json)
  }

  async function deleteItemFromCart () {
    const data = {
      cartItem: {
        productId: 1,
        productVariantId: 1,
        quantity: 1,
      },
    }
    const result = await fetch("{{= route('/store/me/cart/item', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-HTTP-Method-Override': 'DELETE',
      },
      credentials: 'include',
      body: JSON.stringify(data),
    })
    const json = await result.json()
    console.log(json)
  }

  async function getFavorites () {
    const result = await fetch("{{= route('/store/me/products/favorites', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
    })
    const json = await result.json()
    console.log(json)
  }

  async function addFavorite () {
    const data = {
      product: {
        productId: 1,
      },
    }
    const result = await fetch("{{= route('/store/me/products/favorites', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data),
    })
    const json = await result.json()
    console.log(json)
  }

  async function deleteFavorite () {
    const data = {
      product: {
        productId: 1,
      },
    }
    const result = await fetch("{{= route('/store/me/products/favorites', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-HTTP-Method-Override': 'DELETE',
      },
      credentials: 'include',
      body: JSON.stringify(data),
    })
    const json = await result.json()
    console.log(json)
  }

  async function getViews () {
    const result = await fetch("{{= route('/store/me/products/views', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
    })
    const json = await result.json()
    console.log(json)
  }

  async function addView () {
    const data = {
      product: {
        productId: 1,
      },
    }
    const result = await fetch("{{= route('/store/me/products/views', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(data),
    })
    const json = await result.json()
    console.log(json)
  }

  async function deleteViews () {
    const result = await fetch("{{= route('/store/me/products/views', ['storeKey' => $storeContext->getStoreKey()]) }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-HTTP-Method-Override': 'DELETE',
      },
      credentials: 'include',
      body: JSON.stringify({}),
    })
    const json = await result.json()
    console.log(json)
  }

  async function getProducts() {
    const params =  new URLSearchParams([1,2].map((s, i) => ['products[products][' + i + '][id]', s])).toString();
    const result = await fetch("{{= route('/store/common/products', ['storeKey' => $storeContext->getStoreKey()]) }}?" + params, {
      method: 'GET',
      credentials: 'include',
    })
    const json = await result.json()
    console.log(json)
  }
</script>
{{ endBlock () }}

{{ setBlock ('body_content') }}
INDEX

<div>
    <button type="button" onclick="getCart()">Get cart</button>
    <button type="button" onclick="addItemToCart()">Add item to cart</button>
    <button type="button" onclick="deleteItemFromCart()">Delete item from cart</button>
</div>

<div>
    <button type="button" onclick="getFavorites()">Get favorite products</button>
    <button type="button" onclick="addFavorite()">Add favorite product</button>
    <button type="button" onclick="deleteFavorite()">Delete favorite product</button>
</div>

<div>
    <button type="button" onclick="getViews()">Get view products</button>
    <button type="button" onclick="addView()">Add view product</button>
    <button type="button" onclick="deleteViews()">Delete view product</button>
</div>

<div>
    <button type="button" onclick="getProducts()">Get products</button>
</div>
{{ endBlock () }}
