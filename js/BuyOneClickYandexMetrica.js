/**
 * Передача данных в сервис Яндекс Метрика
 * E-commerce: https://yandex.ru/support/metrica/ecommerce/data.html
 */
class BuyOneClickYandexMetrica {

  /**
   * Передача данных в электронную коммерцию
   *
   * @param dataLayerId Имя слоя из метрики
   * @param products Массив товаров
   * @param goalId Ид цели
   * @param purchaseId Идентификатор покупки
   */
  ecommercePurchase = (dataLayerId, products, goalId, purchaseId) => {
    if (typeof window[dataLayerId] === 'undefined') {
      dataLayerId = "dataLayer";
    }
    if (typeof window[dataLayerId] === 'undefined') {
      return;
    }
    window[dataLayerId].push({
      "ecommerce": {
        "purchase": {
          "actionField": {
            "id" : purchaseId,
            "goal_id": goalId,
          },
          "products": products
        }
      }
    });
  };
}