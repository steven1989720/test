# Дано:
# двумерный массив, суть – «прайс-лист» товаров (одинаковых) от разных поставщиков. Каждая строка содержит поля:
# •	id – идентификатор предложения, уникальный в пределах таблицы (int)
# •	count – количество товара на складе (int, >0)
# •	price – цена товара (float, >0)
# •	pack – «кратность» товара (int, >0). Означает, что поставщик по данному предложению может продать товар только партиями, кратными значению pack.
# Исходный массив не отсортирован.
# Также дано число N (int) – «потребность», т.е. количество единиц товара, которые необходимо закупить.
# Ограничения на входные данные:
# Максимальное количество строк в прайс-листе – 1000.
# Максимальное значение N – 10000.
# Максимальное значение для параметра pack – 500.
# Максимальное количество «вариаций» (т.е. различных значений) параметра pack в пределах одной таблицы – 20.
# Задача: 
# определить оптимальный (самый дешевый по цене) «план» закупки, т.е. какие строки и в каком количестве необходимо выбрать для закупки, чтобы итоговая сумма была минимальной.
# Задача может не иметь решения. Например, в случае, когда число N больше, чем сумма count по всей таблице. Или, к примеру, все строки прайса имеют параметр pack=10, а число N=13. В этих случаях на выходе должен быть «пустой» набор. В случае, когда «план» закупки можно составить, на выходе должен быть массив элементов, содержащих поля {id, qty}, где id – идентификатор предложения из исходного массива, qty – то количество, которое необходимо у него закупить.
# Подсказка: необходимо обратить особое внимание на поле pack – оно ключевое в данной задаче и очень сильно влияет на сложность.

# SINCE pack doesn't have common attribute, I can't use dynamic programming algorithm!

import copy

min_price = float('inf')
def find_min_cost(offers, N):
    global min_price
    orders = []
    min_price = float('inf')

    def dp(start, N, total_price, order_list, min_order):
        global min_price

        ret = None
        for i in range(start, len(offers)):
            offer_id, count, price, pack = offers[i]
            order_list.append([offer_id, 0])
            for qty in range(pack, count + 1, pack):
                order_list[len(order_list) - 1][1] = qty
                
                pack_price = price * qty

                if (N == qty):
                    if (total_price + pack_price < min_price):
                        min_order.clear()
                        min_order.extend(copy.deepcopy(order_list))
                        min_price = total_price + pack_price
                    break
                else:
                    dp(i + 1, N - qty, total_price + pack_price, order_list, min_order)
            
            order_list.pop()

    dp(0, N, 0, [], orders)

    return orders

# Define the data from the example provided
N = 76
data = [
    # id, count, price, pack
    (111, 42, 13, 1),
    (222, 77, 11, 10),
    (333, 103, 10, 50),
    (444, 65, 12, 5)
]
print(find_min_cost(data, N)) # [[111,1],[222,20],[333,50],[444,5]]

data = [
    # id, count, price, pack
    (111, 42, 9, 1),
    (222, 77, 11, 10),
    (333, 103, 10, 50),
    (444, 65, 12, 5)
]
print(find_min_cost(data, N)) # [[111,26],[333,50]]

data = [
    # id, count, price, pack
    (111, 100, 30, 1),
    (222, 60, 11, 10),
    (333, 100, 13, 50),
]
print(find_min_cost(data, N)) # [[111,6],[222,20],[333,50]]
