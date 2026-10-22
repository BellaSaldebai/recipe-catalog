# 🗄 Дерекқордың ER-диаграммасы (ER Diagram)

Жоба MySQL дерекқорындағы 3 негізгі кестеден және олардың арасындағы байланыстардан тұрады.

```mermaid
erDiagram
    USERS {
        int id PK "PRIMARY KEY, AUTO_INCREMENT"
        string username "UNIQUE"
        string email "UNIQUE"
        string password "password_hash()"
        string role "ENUM('user', 'admin')"
        timestamp created_at
    }

    CATEGORIES {
        int id PK "PRIMARY KEY, AUTO_INCREMENT"
        string name "Санат атауы"
        string slug "URL атауы"
    }

    RECIPES {
        int id PK "PRIMARY KEY, AUTO_INCREMENT"
        int user_id FK "FOREIGN KEY -> USERS.id"
        int category_id FK "FOREIGN KEY -> CATEGORIES.id"
        string title "Рецепт атауы"
        text description "Қысқаша сипаттама"
        text ingredients "Ингредиенттер"
        text instructions "Дайындалу жолы"
        int cooking_time "Уақыты (мин)"
        string image "Сурет сілтемесі"
        timestamp created_at
    }

    USERS ||--o{ RECIPES : "жариялайды (1:N)"
    CATEGORIES ||--o{ RECIPES : "қамтиды (1:N)"
