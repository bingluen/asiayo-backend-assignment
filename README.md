# AssiaYo Backend assignment

## 開發環境

本專案以 laravel 開發

- PHP ^7.3 / ^8.0

## 如何執行

**安裝相依套件**

```
composer install
```

**執行**

```
php artisan serve
```

## API

URIs Relative to `{hostname}/api`

| HTTP Request                  | 描述                 |
| ----------------------------- | -------------------- |
| `GET` **/currency**           | 支援的貨幣列表及匯率 |
| `POST` **/currency/exchange** | 換匯估算             |

### `GET` `/api/currency` 支援的貨幣及匯率

`日幣` 轉換為 `台幣` 之匯率以 `data.JPY.TWD` 表示

#### Request

**HTTTP Request**

```
GET {hostname}/api/currency
```

**Parameters**

No parameters need.

#### Response

```json
{
    "data": {
        "TWD": {
            "TWD": 1,
            "JPY": 3.669,
            "USD": 0.03281
        },
        "JPY": {
            "TWD": 0.26956,
            "JPY": 1,
            "USD": 0.00885
        },
        "USD": {
            "TWD": 30.444,
            "JPY": 111.801,
            "USD": 1
        }
    }
}
```

### `POST` `/api/currency/exchange` 換匯估算

#### Request

**HTTTP Request**

```
POST {hostname}/api/currency/exchange
```

**Parameters**

No parameters need.

**Request Body**

| Property Name | Value  | Description                               |
| ------------- | ------ | ----------------------------------------- |
| currency      | string | 目標幣別，`/api/currency` 所列之一的貨幣  |
| payment       | string | 原始幣別，`/api/currency`  所列之一的貨幣 |
| amount        | float  | 原始幣別金額                              |

Request sample

```json
{
    "currency": "JPY",
    "payment": "TWD",
    "amount": 100
}
```

**Response**

`200`

Response schema: `application/json`

```json
{
    "data": {
        "amount": "366.90"
    }
}
```

`400`

Response schema: `application/json`

```json
{
  "errors": {
    "payment": [
      "The selected payment is invalid."
    ]
  }
}
```

