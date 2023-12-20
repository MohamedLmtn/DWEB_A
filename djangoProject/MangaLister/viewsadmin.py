from django.shortcuts import render, redirect
from django.http import HttpResponse
from MangaLister.models import Category
from MangaLister.models import Manga
from django.views.decorators.csrf import csrf_exempt
from MangaLister.models import MangaCategory

def adminpagemanga(request):
    lstcat = ""
    for Cat in Category.objects.all():
        lstcat += f"{Cat.id}.{Cat.name} "

    return render(request, 'mangaadmin.html',
                  {'listallmanga': Manga.objects.all(), 'listcategorie': Category.objects.all(), "catString": lstcat})


def adminpagecat(request):
    lstcat = ""
    for Cat in Category.objects.all():
        lstcat += f"{Cat.id}.{Cat.name} "

    return render(request, 'categorieadmin.html',
                  {'listcategorie': Category.objects.all(), "catString": lstcat})
