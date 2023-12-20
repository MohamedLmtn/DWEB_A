from django.shortcuts import render, redirect
from django.http import HttpResponse
from MangaLister.models import Category
from MangaLister.models import Manga
from django.views.decorators.csrf import csrf_exempt
from MangaLister.models import MangaCategory



def htmlbodybase(request):
    context = {'listallmanga': Manga.objects.all(),
               'listcategorie': Category.objects.all()}

    return render(request, 'index.html', context)


def htmladdMangaDetails(request):
    if request.method == 'POST':
        MangaCat = request.POST.get('categ', '')
        mangas_filtered = Manga.objects.filter(mangacategory__category__name=MangaCat)

        context = {
            'listallmanga': mangas_filtered,
            'listcategorie': Category.objects.all()
        }

    return render(request, 'index.html', context)
