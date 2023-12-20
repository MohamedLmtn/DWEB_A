from django.db import models


class Manga(models.Model):
    title = models.CharField(max_length=100)
    author = models.CharField(max_length=100)

    def __str__(self):
        return self.title


class Category(models.Model):
    name = models.CharField(max_length=50)

    def __str__(self):
        return self.name


class MangaCategory(models.Model):
    manga = models.ForeignKey(Manga, on_delete=models.CASCADE)
    category = models.ForeignKey(Category, on_delete=models.CASCADE)
