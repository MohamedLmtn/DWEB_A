from django.shortcuts import render, redirect
from django.http import HttpResponse
from MangaLister.models import Category
from MangaLister.models import Manga
from django.views.decorators.csrf import csrf_exempt
from MangaLister.models import MangaCategory


def traitementadmin(request, parametre=None):
    if parametre is not None:
        Manga.objects.filter(id=parametre).delete()
    elif request.POST.get('typetraitement') == 'Update':
        idmanga = request.POST.get('idmanga', '')
        try:
            manga = Manga.objects.get(id=idmanga)
            new_title = request.POST.get('nommanga', '')
            new_author = request.POST.get('nomautheur', '')
            catmanga = request.POST.get('catmanga', '')

            if manga.title != new_title:
                manga.title = new_title
            if manga.author != new_author:
                manga.author = new_author

            categoriemanga = MangaCategory.objects.get(manga_id=idmanga)
            if categoriemanga.category_id != catmanga:
                categoriemanga.category_id = catmanga
            categoriemanga.save()
            manga.save()
        except Manga.DoesNotExist:
            pass
    elif request.POST.get('typetraitement') == 'Add':
        catmanga = request.POST.get('catmanga', '')
        new_title = request.POST.get('nommanga', '')
        new_author = request.POST.get('nomautheur', '')

        if new_title and new_author:
            nvmanga = Manga.objects.create(title=new_title, author=new_author)
            nvmanga.save()

        if catmanga:
            nvcategorie = MangaCategory.objects.create(category_id=catmanga, manga_id=nvmanga.id)
            nvcategorie.save()
    return redirect('/home/')